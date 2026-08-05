<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'id_user', 'user_id', 'id_wisata', 'destinasi_id', 'id_penginapan', 'id_kuliner', 'komentar', 'rating', 'skor_rating', 'gambar'
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
            'skor_rating' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Getter destinasi_id virtual dari kolom id_wisata / id_penginapan / id_kuliner
     */
    public function getDestinasiIdAttribute()
    {
        return $this->attributes['destinasi_id'] ?? $this->id_wisata ?? null;
    }

    /**
     * Mutator destinasi_id virtual untuk memisahkan input ke kolom yang tepat
     */
    public function setDestinasiIdAttribute($value)
    {
        $this->attributes['destinasi_id'] = $value;

        if (!$value) {
            $this->attributes['id_wisata'] = null;
            $this->attributes['id_kuliner'] = null;
            $this->attributes['id_penginapan'] = null;
            return;
        }

        $id = (int)$value;
        if ($id < 1000000) {
            $this->attributes['id_wisata'] = $id;
            $this->attributes['id_kuliner'] = null;
            $this->attributes['id_penginapan'] = null;
        } elseif ($id < 2000000) {
            $this->attributes['id_wisata'] = null;
            $this->attributes['id_kuliner'] = $id - 1000000;
            $this->attributes['id_penginapan'] = null;
        } else {
            $this->attributes['id_wisata'] = null;
            $this->attributes['id_kuliner'] = null;
            $this->attributes['id_penginapan'] = $id - 2000000;
        }
    }

    /**
     * Alias skor_rating ke rating
     */
    public function getSkorRatingAttribute()
    {
        return $this->attributes['skor_rating'] ?? $this->attributes['rating'] ?? 0;
    }

    public function setSkorRatingAttribute($value)
    {
        $this->attributes['skor_rating'] = $value;
        $this->attributes['rating'] = $value;
    }

    /**
     * Alias user_id ke id_user
     */
    public function getUserIdAttribute()
    {
        return $this->id_user;
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['id_user'] = $value;
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'id_wisata', 'id'); // fallback relation, but view query works best via accessor
    }

    public function getReviewAttribute(): ?string
    {
        return $this->komentar;
    }

    public function getRateableAttribute(): ?Destinasi
    {
        $destId = $this->destinasi_id;
        if ($destId) {
            return Destinasi::find($destId);
        }
        return null;
    }

    public function getRateableTypeAttribute(): string
    {
        $destId = $this->destinasi_id;
        if ($destId) {
            if ($destId < 1000000) {
                return 'Wisata';
            } elseif ($destId < 2000000) {
                return 'Kuliner';
            } else {
                return 'Penginapan';
            }
        }
        return 'Destinasi';
    }

    /**
     * Filter query pencarian berdasarkan destinasi_id virtual
     */
    public static function queryByDestinasi($query, $destinasiId)
    {
        $id = (int)$destinasiId;
        if ($id < 1000000) {
            return $query->where('id_wisata', $id);
        } elseif ($id < 2000000) {
            return $query->where('id_kuliner', $id - 1000000);
        } else {
            return $query->where('id_penginapan', $id - 2000000);
        }
    }

    /**
     * Calculate Bayesian Average untuk destinasi
     */
    public static function calculateBayesianAverage($destinasiId, $confidenceThreshold = 5)
    {
        // Hitung global average rating dari semua destinasi
        $globalAverage = Rating::avg('rating') ?? 0;
        
        // Hitung rating item ini
        $query = Rating::query();
        $query = self::queryByDestinasi($query, $destinasiId);
        $itemRating = $query->avg('rating') ?? 0;
        
        $queryVotes = Rating::query();
        $queryVotes = self::queryByDestinasi($queryVotes, $destinasiId);
        $itemVotes = $queryVotes->count();
        
        // Formula Bayesian Average
        $bayesianAvg = ($confidenceThreshold * $globalAverage + $itemVotes * $itemRating) 
                        / ($confidenceThreshold + $itemVotes);
        
        return round($bayesianAvg, 2);
    }

    /**
     * Update rating destinasi menggunakan Bayesian Average
     */
    public static function updateDestinationRating($destinasiId)
    {
        $bayesianAvg = self::calculateBayesianAverage($destinasiId);
        
        $id = (int)$destinasiId;
        if ($id < 1000000) {
            DB::table('wisata')->where('id_wisata', $id)->update([
                'trend' => $bayesianAvg // or whatever rating column they use in original table
            ]);
        } elseif ($id < 2000000) {
            DB::table('kuliner')->where('id_kuliner', $id - 1000000)->update([
                'trend' => $bayesianAvg
            ]);
        } else {
            DB::table('penginapan')->where('id_penginapan', $id - 2000000)->update([
                'trend' => $bayesianAvg
            ]);
        }
    }
}