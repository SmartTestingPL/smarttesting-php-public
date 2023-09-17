<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Credit;

final class CreditInfo
{
    /*
     * Aktualne zadłużenie (spłacane kredyty, pożyczki, ale także posiadane karty kredytowe czy limity w rachunku, ze szczególnym uwzględnieniem wysokości raty innych kredytów)
     */
    private float $currentDebt;

    /*
     * Koszty utrzymania kredytobiorcy i jego rodziny;
     */
    private float $currentLivingCosts;

    /*
     * Historia kredytowa (sposób, w jaki kredytobiorca spłacał dotychczasowe zobowiązania);
     */
    private DebtPaymentHistory $debtPaymentHistory;

    public function __construct(float $currentDebt, float $currentLivingCosts, DebtPaymentHistory $debtPaymentHistory)
    {
        $this->currentDebt = $currentDebt;
        $this->currentLivingCosts = $currentLivingCosts;
        $this->debtPaymentHistory = $debtPaymentHistory;
    }

    public function currentDebt(): float
    {
        return $this->currentDebt;
    }

    public function currentLivingCosts(): float
    {
        return $this->currentLivingCosts;
    }

    public function debtPaymentHistory(): DebtPaymentHistory
    {
        return $this->debtPaymentHistory;
    }
}
