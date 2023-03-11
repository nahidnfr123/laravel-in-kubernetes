<?php

namespace App\Http\Controllers;

use Aws\Sdk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function getAwsUrl(Request $request): JsonResponse
    {
        $rules = [
            'name' => 'required'
        ];
        $this->validate($request, $rules);

        $sdk = new Sdk([
            'region' => config('filesystems.disks.s3.region', null),
            'version' => 'latest'
        ]);
        $client = $sdk->createS3();
        $expiry = '+1440 minutes';
        $cmd = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => 'tmp/' . $request->name,
            'ACL' => 'public-read',
        ]);

        $request = $client->createPresignedRequest($cmd, $expiry);

        $url = (string)$request->getUri();

        return response()->json(['url' => $url], 201);
    }
}
