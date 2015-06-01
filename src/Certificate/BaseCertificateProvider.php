<?php  namespace Develpr\AlexaApp\Certificate;

abstract class BaseCertificateProvider {

	const ECHO_SERVICE_DOMAIN = 'echo-api.amazon.com';

	/**
	 * @param $certificate
	 * @return array | null
	 */
	protected function parseCertificate($certificate){

		return openssl_x509_parse($certificate);

	}

	/**
	 * returns true if the configured service domain is present/valid, false if invalid/not present
	 * @param array $parsedCertificate
	 * @return boolean
	 */
	protected function verifyCertificateSubjectAltNamePresent(array $parsedCertificate)
	{
		if(strpos(array_get($parsedCertificate, 'extensions.subjectAltName'), self::ECHO_SERVICE_DOMAIN) === false)
			return false;
		else
			return true;
	}

	/**
	 * returns true if the date is valid, false if not
	 *
	 * @param array $parsedCertificate
	 * @return boolean
	 */
	protected function validateCertificateDate(array $parsedCertificate){

		$validFrom = array_get($parsedCertificate, 'validFrom_time_t');

		$validTo = array_get($parsedCertificate, 'validTo_time_t');

		$time = time();

		return ($validFrom <= $time && $time <= $validTo);

	}

	/**
	 * Retrieve the certificate from a url
	 *
	 * @param $certificateChainUri
	 * @return string
	 */
	protected function getRemoteCertificateChain($certificateChainUri){

		return file_get_contents($certificateChainUri);

	}
} 