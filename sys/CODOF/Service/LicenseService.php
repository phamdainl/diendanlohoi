<?php

namespace CODOF\Service;

use GuzzleHttp;

/**
 * Description of LicenseService
 *
 * @author silva
 */
class LicenseService {

    public function reloadLicenseInfoForKey($licenseKey): bool {
        $clientId = $licenseKey;
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $this->getServerURL() . '/api/v1/subscription/client/' . $clientId);
        if ($response->getStatusCode() != 200) {
            return false;
        }
        $resultObj = json_decode($response->getBody()->getContents(), true);
        if ($resultObj['success']) {
            \CODOF\Util::set_opt('CF_LICENSE_MACHINE', str_rot13($resultObj['data']['machineName']));
            \CODOF\Util::set_opt('CF_LICENSE_EXPIRES_AT', $resultObj['data']['expiresAt']);
            return true;
        } else {
            return false;
        }
    }

    public function reloadLicenseInfo(): void {

        $licenseKey = \CODOF\Util::get_opt('CF_LICENSE_KEY');
        if (empty($licenseKey)) {
            return;
        }
        $this->reloadLicenseInfoForKey($licenseKey);
    }

    public function getServerURL(): string {
        return \CODOF\Util::get_opt('codoforum_server');
    }

}
