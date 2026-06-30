<?php

// SPDX-License-Identifier: EUPL-1.2
// SPDX-FileCopyrightText: 2026 public-sector-dev-crew

declare(strict_types=1);

namespace Lotse\Sonde;

use Lotse\Rigg\Sonde\SondeProbeInterface;
use Lotse\Rigg\Sonde\SondeVerdict;

/**
 * Führt einen Korpus von {@see SondeProbeInterface} gegen die echten Schutzklassen und sammelt die
 * Urteile (DESIGN-08). Aktuell der Regressions-Runner und Grundlage für einen späteren Sonde-Adversarial-Agenten.
 *
 * **fail-closed:** Wirft eine Sonde aus `isDefended()` einen unerwarteten Fehler, gilt der Fall als
 * **Bruch** (`defended = false`) — eine Sonde, die ihre Abwehr nicht bestätigen kann, ist kein „grün".
 */
final readonly class SondeRunner
{
    /**
     * @param iterable<SondeProbeInterface> $probes
     */
    public function run(iterable $probes): SondeReport
    {
        $verdicts = [];

        foreach ($probes as $probe) {
            try {
                $verdicts[] = new SondeVerdict($probe->id(), $probe->attackClass(), $probe->tier(), $probe->isDefended());
            } catch (\Throwable $e) {
                $verdicts[] = new SondeVerdict(
                    $probe->id(),
                    $probe->attackClass(),
                    $probe->tier(),
                    false,
                    'Sonde inkonklusiv (fail-closed): '.$e::class,
                );
            }
        }

        return new SondeReport($verdicts);
    }
}
