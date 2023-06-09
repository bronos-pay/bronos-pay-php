<?php

namespace BronosPay\Services;

class PublicService extends AbstractService
{
    /**
     * Current exchange rate for any two currencies, fiat or crypto.
     *
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function getExchangeRate(string $from, string $to)
    {
        return $this->request('get', $this->buildPath('/api/v1/rates/merchant/%s/%s', $from, $to));
    }

    /**
     * Current BronosPay exchange rates for Merchants and Traders.
     *
     * @return mixed
     */
    public function listExchangeRates()
    {
        return $this->request('get', '/api/v1/rates');
    }

    /**
     * Get IP addresses of BronosPay servers
     *
     * @param string $separator
     * @return mixed
     */
    public function getIPAddresses(string $separator = '\n')
    {
        return $this->request('get', '/api/v1/ips-v4', [
            'separator' => $separator
        ]);
    }

    /**
     * @param string|null $kind
     * @param bool $native
     * @param bool $merchantPay
     * @param bool $merchantReceive
     * @param bool $enabledOnly
     * @return mixed
     */
    public function getCurrencies(
        string $kind = null,
        bool $native = false,
        bool $merchantPay = false,
        bool $merchantReceive = false,
        bool $enabledOnly = true
    ) {
        return $this->request('get', '/api/v1/currencies', array_filter([
            'kind' => $kind,
            'native' => $native,
            'merchant_pay' => $merchantPay,
            'merchant_receive' => $merchantReceive,
            'enabled' => $enabledOnly
        ]));
    }

    /**
     * @return mixed
     */
    public function getCheckoutCurrencies()
    {
        return $this->getCurrencies('crypto', true, true);
    }

    /**
     * @param string|null $kind
     * @return mixed
     */
    public function getMerchantPayCurrencies(string $kind = null)
    {
        return $this->getCurrencies($kind, false, true);
    }

    /**
     * @param string|null $kind
     * @return mixed
     */
    public function getMerchantPayoutCurrencies(string $kind = null)
    {
        return $this->getCurrencies($kind, false, false, true);
    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getPlatforms(bool $enabled = true)
    {
        return $this->request('get', '/api/v1/platforms', array_filter(['enabled' => $enabled]));
    }

    /**
     * A health check endpoint for BronosPay API.
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->request('get', '/api/v1/ping');
    }
}
