<?php

namespace App\Support;

class BloodCompatibility
{
    /**
     * Standard ABO/Rh whole-blood compatibility: for each RECIPIENT blood
     * group, which DONOR blood groups can safely give to them. O- is the
     * universal donor; AB+ is the universal recipient.
     */
    private const DONOR_GROUPS_BY_RECIPIENT = [
        'O-'  => ['O-'],
        'O+'  => ['O-', 'O+'],
        'A-'  => ['O-', 'A-'],
        'A+'  => ['O-', 'O+', 'A-', 'A+'],
        'B-'  => ['O-', 'B-'],
        'B+'  => ['O-', 'O+', 'B-', 'B+'],
        'AB-' => ['O-', 'A-', 'B-', 'AB-'],
        'AB+' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
    ];

    /**
     * Which donor blood groups can safely donate to the given recipient
     * blood group. Falls back to an exact-match-only list for an
     * unrecognised group rather than silently matching everyone.
     */
    public static function compatibleDonorGroups(string $recipientGroup): array
    {
        return self::DONOR_GROUPS_BY_RECIPIENT[$recipientGroup] ?? [$recipientGroup];
    }

    public static function isExactMatch(string $donorGroup, string $recipientGroup): bool
    {
        return $donorGroup === $recipientGroup;
    }
}
