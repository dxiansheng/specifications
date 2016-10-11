<?php

namespace Pbmedia\Specifications;

use Illuminate\Support\Collection;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;
use Pbmedia\Specifications\Specifications;

class Matcher implements CanBeSpecified
{
    use HasSpecifications;

    /**
     * Instance of Specifications.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $candidates;

    /**
     * Create a new Matches instance and instantiates a new Collection.
     */
    public function __construct()
    {
        $this->candidates = new Collection;
    }

    /**
     * Add an object that implements the CanBeSpecified interface.
     *
     * @param  \Pbmedia\Specifications\Interfaces\CanBeSpecified   $candidate
     * @return $this
     */
    public function addCandidate(CanBeSpecified $candidate): Matcher
    {
        $this->candidates->push($candidate);

        return $this;
    }

    /**
     * Helper method to add multiple candidates at once.
     *
     * @param  mixed   $candidates
     * @return $this
     */
    public function addCandidates($candidates): Matcher
    {
        $candidates = is_array($candidates) ? $candidates : func_get_args();

        Collection::make($candidates)->each(function ($candidate) {
            $this->addCandidate($candidate);
        });

        return $this;
    }

    /**
     * Returns a collection containing all candidates.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    /**
     * Returns a collection where the keys matches the keys of the
     * candidates Collection but contain the score of the given
     * Attribute object.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @return \Illuminate\Support\Collection
     */
    public function getScoresByAttribute(Attribute $attribute): Collection
    {
        return $this->candidates->map(function ($candidate) {
            return $candidate->specifications();
        })->map(function ($specifications) use ($attribute) {
            if (!$specifications->has($attribute)) {
                return;
            }

            return $specifications->get($attribute)->getScoreValue();
        });
    }

    /**
     * This method does the same as the 'getScoresByAttribute' method
     * but has the scores normalized.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @return \Illuminate\Support\Collection
     */
    public function getNormalizedScoresByAttribute(Attribute $attribute): Collection
    {
        $scores = $this->getScoresByAttribute($attribute);

        $max = $scores->max();

        return $scores->map(function ($score) use ($max) {
            return is_null($score) ? null : ($score / $max);
        });
    }

    /**
     * Returns a collection where the keys matches the keys of the
     * candidates Collection but contain the normalized score compaired
     * to the given AttributeScore.
     *
     * @param  \Pbmedia\Specifications\AttributeScore  $attributeScore
     * @return \Illuminate\Support\Collection
     */
    public function getMatchingScoreByAttributeScore(AttributeScore $attributeScore): Collection
    {
        $attribute  = $attributeScore->getAttribute();
        $scoreValue = $attributeScore->getScoreValue();

        $scores = $this->getScoresByAttribute($attribute);

        if (!$scores->max()) {
            return $this->candidates->map(function () {
                return 0;
            });
        }

        $scoreToCompareTo = $scoreValue / $scores->max();

        return $this->getNormalizedScoresByAttribute($attribute)->map(function ($normalizedScore) use ($scoreToCompareTo) {
            if ($normalizedScore === null) {
                return null;
            }

            return 1 - abs($normalizedScore - $scoreToCompareTo);
        });
    }

    /**
     * Returns a collection where the candidaties are sorted based
     * on how close they are to the specifications.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        if ($this->specifications()->all()->isEmpty()) {
            return $this->getCandidates();
        }

        $scores = [];

        $this->specifications()->all()->map(function (AttributeScore $attributeScore) {
            return $this->getMatchingScoreByAttributeScore($attributeScore);
        })->each(function ($matchingScoreByAttributeScore) use (&$scores) {
            $matchingScoreByAttributeScore->each(function ($score, $productKey) use (&$scores) {
                if (!isset($scores[$productKey])) {
                    $scores[$productKey] = 0;
                }

                $scores[$productKey] += $score;
            });
        });

        return $this->getCandidates()->map(function (CanBeSpecified $candidate, $productKey) use ($scores) {
            $score = $scores[$productKey];

            return compact('candidate', 'score');
        })->sortByDesc('score')->map(function ($candidateWithScore): CanBeSpecified {
            return $candidateWithScore['candidate'];
        })->values();
    }
}
