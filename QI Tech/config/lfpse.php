<?php

return [
    "ocp_apim_subscription_key" => env("LFPSE_OCP_APIM_SUBSCRIPTION_KEY", "25cef71dcfe64d5496840305c8d30819"), // this is a test key, use production key in env
    
    "api_endpoint" => "https://psims-uat.azure-api.net",
    "api_endpoint_6" => "https://developer.learn-from-patient-safety-events.nhs.uk",
    "request_timeout_seconds" => 40,

    "service_active" => true,
];