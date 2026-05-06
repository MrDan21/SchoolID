<?php

namespace App\Services;

use InvalidArgumentException;

class FaceMatchingService
{
    public function calculateEuclideanDistance(array $baseEmbedding, array $newEmbedding): float
    {
        if (count($baseEmbedding) !== count($newEmbedding)) {
            throw new \InvalidArgumentException("Los embeddings deben tener la misma longitud.");
        }

        $sum = 0.0;
        foreach ($baseEmbedding as $index => $value) {
            $difference = $value - $newEmbedding[$index];
            $sum += ($diff * $diff);
        }

        return sqrt($sum);
    }

    public function isMatch(array $baseEmbedding, array $newEmbedding, float $threshold = 0.5): bool
    {
        return $this->calculateEuclideanDistance($baseEmbedding, $newEmbedding) <= $threshold;
    }
}
