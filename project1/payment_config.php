<?php
// Toggle these booleans per environment.
const KHALTI_IS_SANDBOX = true;
const ESEWA_IS_SANDBOX = true;

// Khalti credentials and URLs
const KHALTI_SANDBOX_SECRET_KEY = 'test_secret_key_here';
const KHALTI_LIVE_SECRET_KEY = 'live_secret_key_here';
const KHALTI_SANDBOX_INITIATE_URL = 'https://dev.khalti.com/api/v2/epayment/initiate/';
const KHALTI_SANDBOX_LOOKUP_URL = 'https://dev.khalti.com/api/v2/epayment/lookup/';
const KHALTI_LIVE_INITIATE_URL = 'https://khalti.com/api/v2/epayment/initiate/';
const KHALTI_LIVE_LOOKUP_URL = 'https://khalti.com/api/v2/epayment/lookup/';

const KHALTI_SECRET_KEY = KHALTI_IS_SANDBOX ? KHALTI_SANDBOX_SECRET_KEY : KHALTI_LIVE_SECRET_KEY;
const KHALTI_INITIATE_URL = KHALTI_IS_SANDBOX ? KHALTI_SANDBOX_INITIATE_URL : KHALTI_LIVE_INITIATE_URL;
const KHALTI_LOOKUP_URL = KHALTI_IS_SANDBOX ? KHALTI_SANDBOX_LOOKUP_URL : KHALTI_LIVE_LOOKUP_URL;

// eSewa credentials and URLs
const ESEWA_SANDBOX_MERCHANT_CODE = 'EPAYTEST';
const ESEWA_SANDBOX_SECRET = '8gBm/:&EnhH.1/q';
const ESEWA_SANDBOX_FORM_URL = 'https://rc-epay.esewa.com.np/api/epay/main/v2/form';

const ESEWA_LIVE_MERCHANT_CODE = 'YOUR_LIVE_MERCHANT_CODE';
const ESEWA_LIVE_SECRET = 'YOUR_LIVE_SECRET';
const ESEWA_LIVE_FORM_URL = 'https://epay.esewa.com.np/api/epay/main/v2/form';

const ESEWA_MERCHANT_CODE = ESEWA_IS_SANDBOX ? ESEWA_SANDBOX_MERCHANT_CODE : ESEWA_LIVE_MERCHANT_CODE;
const ESEWA_SECRET = ESEWA_IS_SANDBOX ? ESEWA_SANDBOX_SECRET : ESEWA_LIVE_SECRET;
const ESEWA_FORM_URL = ESEWA_IS_SANDBOX ? ESEWA_SANDBOX_FORM_URL : ESEWA_LIVE_FORM_URL;
