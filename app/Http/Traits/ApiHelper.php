<?php

namespace App\Http\Traits;

trait ApiHelper
{

    /**
     * Check if token has admin ability
     * @param $user
     * @return bool
     */
    protected function isAdmin($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;

    }

    /**
     * Проверка на роль менеджера (представителя)
     * @param $user
     * @return bool
     */
    protected function isManager($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('manager');
        }

        return false;

    }

    /**
     * Проверка на роль заявителя
     * @param $user
     * @return false
     */
    protected function isApplicant($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('applicant');
        }

        return false;

    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function onSuccess($data, string $message = '', int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function onError(int $code, string $message = ''): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    /**
     * Валидация Creating/Updating Application
     * @return string[]
     */
    protected function applicValidationRules(): array
    {
        return [
            'text' => 'required|max:255',
            'coordinates.lon' => ['required', 'regex:/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?))$/i'],
            'coordinates.lat' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/i'],
            'docs.*' => 'file|mimes:pdf,jpg,doc',
            'docs' => 'max:5',
        ];
    }

    /**
     * Валидация при получении списка городов
     * @return string[]
     */
    protected function townValidationRules(): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }


}