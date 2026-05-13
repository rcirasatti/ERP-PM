<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DSSPredictionService
{
    /**
     * Perform the Linear Regression prediction natively in PHP.
     * This avoids cold-starting Python and loads instantly.
     * 
     * @param string $jenisPekerjaan
     * @param string $wilayah
     * @param float $grandTotal
     * @return float Predicted actual cost
     * @throws \Exception
     */
    public static function predict(string $jenisPekerjaan, string $wilayah, float $grandTotal): float
    {
        $jsonPath = storage_path('app/ml/model_parameters.json');
        
        if (!file_exists($jsonPath)) {
            throw new \Exception("Model parameters file not found at {$jsonPath}. Please generate it first.");
        }
        
        $params = json_decode(file_get_contents($jsonPath), true);
        
        if (!isset($params[$jenisPekerjaan])) {
            throw new \Exception("Jenis Pekerjaan '{$jenisPekerjaan}' tidak didukung oleh model.");
        }
        
        $model = $params[$jenisPekerjaan];
        $features = $params['feature_columns'];
        
        // Build feature vector (initialized to 0)
        $vector = array_fill(0, count($features), 0.0);
        
        // 1. Fill Nilai Penawaran (x1)
        $indexPenawaran = array_search('Nilai Penawaran (x1)', $features);
        if ($indexPenawaran !== false) {
            $vector[$indexPenawaran] = $grandTotal;
        }
        
        // 2. Fill Wilayah
        $wilayahKey = "Wilayah (x2)_{$wilayah}";
        $indexWilayah = array_search($wilayahKey, $features);
        if ($indexWilayah !== false) {
            $vector[$indexWilayah] = 1.0;
        }
        
        // 3. Transform X (MinMaxScaler)
        // Formula: X_scaled = X * scale_ + min_
        $xScaled = [];
        for ($i = 0; $i < count($vector); $i++) {
            $val = $vector[$i] * $model['scaler_X_scale'][$i] + $model['scaler_X_min'][$i];
            $xScaled[] = $val;
        }
        
        // 4. Predict (Linear Regression)
        // Formula: pred_scaled = dot(X_scaled, coef) + intercept
        $predScaled = $model['intercept'];
        for ($i = 0; $i < count($xScaled); $i++) {
            $predScaled += $xScaled[$i] * $model['coef'][$i];
        }
        
        // 5. Inverse Transform y (MinMaxScaler)
        // Formula: y = (y_scaled - min_) / scale_
        $pred = ($predScaled - $model['scaler_y_min'][0]) / $model['scaler_y_scale'][0];
        
        return $pred;
    }
}
