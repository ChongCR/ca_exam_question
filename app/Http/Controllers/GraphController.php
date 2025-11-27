<?php

namespace App\Http\Controllers;

class GraphController extends Controller
{
    public function index()
    {
        /*
         *  Todo: calculate Sharpe Ratio, Calmar Ratio, MDD, Annual Return here
         *  Todo: Make sure the next page has a graph
         *
         */

        // Read CSV file
        $csvPath = public_path('sample_data.csv');
        $data = $this->readCsvData($csvPath);

        // Calculate financial metrics
        $metrics = $this->calculateMetrics($data);

        return view('graph.index', compact('data', 'metrics'));
    }

    /**
     * Read CSV data and return as array
     */
    private function readCsvData($filePath)
    {
        $csvData = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle); // Skip header row

            while (($row = fgetcsv($handle)) !== false) {
                $csvData[] = [
                    'date' => $row[0],
                    'pnl' => (float) $row[1],
                    'dd' => (float) $row[2],
                    'equity' => (float) $row[3],
                ];
            }

            fclose($handle);
        }

        return $csvData;
    }

    /**
     * Calculate financial metrics
     */
    private function calculateMetrics($data)
    {
        $pnlValues = array_column($data, 'pnl');
        $ddValues = array_column($data, 'dd');

        // Calculate Annual Return
        $meanPnl = array_sum($pnlValues) / count($pnlValues);
        $annualReturn = $meanPnl * 365;

        // Calculate Sharpe Ratio
        $variance = 0;
        foreach ($pnlValues as $pnl) {
            $variance += pow($pnl - $meanPnl, 2);
        }
        $stdDev = sqrt($variance / count($pnlValues));
        $sharpeRatio = ($meanPnl / $stdDev) * sqrt(365);

        // Calculate Maximum Drawdown
        $maxDrawdown = max($ddValues);

        // Calculate Calmar Ratio
        $calmarRatio = $maxDrawdown != 0 ? $annualReturn / abs($maxDrawdown) : 0;

        return [
            'annual_return' => round($annualReturn * 100, 2), 
            'sharpe_ratio' => round($sharpeRatio, 2),
            'max_drawdown' => round($maxDrawdown * 100, 2), 
            'calmar_ratio' => round($calmarRatio, 2),
        ];
    }
}
