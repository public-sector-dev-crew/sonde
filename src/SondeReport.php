<?php

// SPDX-License-Identifier: EUPL-1.2
// SPDX-FileCopyrightText: 2026 public-sector-dev-crew

declare(strict_types=1);

namespace Lotse\Sonde;

use Lotse\Rigg\Sonde\SondeVerdict;

/**
 * Ergebnis eines Sonde-Laufs über einen Korpus (DESIGN-08). Ein **Bruch** ist jede nicht abgewehrte
 * Angriffsklasse — d. h. eine zurückgedrehte Härtung. Trägt KEINE PII.
 */
final readonly class SondeReport
{
    /**
     * @param list<SondeVerdict> $verdicts
     */
    public function __construct(
        public array $verdicts,
    ) {
    }

    /**
     * @return list<SondeVerdict> die durchgekommenen Angriffsklassen (Regressionen)
     */
    public function breaches(): array
    {
        return array_values(array_filter($this->verdicts, static fn (SondeVerdict $v): bool => !$v->defended));
    }

    public function hasBreaches(): bool
    {
        return [] !== $this->breaches();
    }

    public function count(): int
    {
        return \count($this->verdicts);
    }
}
