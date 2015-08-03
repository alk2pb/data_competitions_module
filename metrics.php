<?php

/**
 * Regression
 */

// Absolute Error
function ae($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $sum += abs($y_pred - $y);
            $n++;
        }
    }

    return $sum;
}

// Mean Absolute Error
function mae($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $sum += abs($y_pred - $y);
            $n++;
        }
    }

    $avg = $sum / $n;

    return $avg;
}

// Weighted Mean Absolute Error
/* Framework for later implementation if needed
function wmae($testingfile, $solutionfile, $predictionstart, $predictionend) {

    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    $count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    for($i = 1; $i < $count; $i++) {
        $testcolumns = explode(",", $testlines[$i]);
        $solutioncolumns = explode(",", $solutionlines[$i]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $sum += abs($y_pred - $y);
            $n++;
        }
    }

    $avg = $sum / $n;

    return sqrt($avg);
}
*/

// Root Mean Squared Error
function rmse($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $sum += pow(($y_pred - $y), 2);
            $n++;
        }
    }

    $avg = $sum / $n;

    return sqrt($avg);
}

// Root Mean Squared Logarithmic Error (stub)
function rmsle($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $sum += pow((log($y_pred) - log($y)), 2);
            $n++;
        }
    }

    $avg = $sum / $n;

    return sqrt($avg);
}

/**
 * Classification
 */

// Logarithmic Loss
function logloss($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    $eps = 0.00001;

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $y = $testcolumns[$j];
            $y_pred = $solutioncolumns[$j];

            $y_pred = min(max($y_pred, $eps), 1-$eps);
            $sum += (($y*log($y_pred)) + ((1-$y)*log(1-$y_pred)));
            $n++;
        }
    }

    $avg = $sum / $n;

    return sqrt(-$avg);
}

// Area Under Curve
function auc($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 1;
    $avg = 0;
    $start = 1;

    $roc_curve = array();

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if ($testcolumns[$j] == 'break') {
                $r = recall(array_slice($testlines, $start, $j - $start), array_slice($solutionlines, $start, $j - $start), $predictioncolumn);
                $f = fprate(array_slice($testlines, $start, $j - $start), array_slice($solutionlines, $start, $j - $start), $predictioncolumn);
                $roc_curve[$f] = $r;

                $start = $j;
                $n++;
            }
        }
    }

    /**
     * Go through lines until break is found. Each break blocks off a group for which true positive rate and false positive rate will be determined.
     * Then find area under curve by using trapezoid approximation
     */

    /**
     * $roc_curve has set of points. Find area under points
     */

    ksort($roc_curve);

    foreach ($roc_curve as $f => $r) {
        $sum += + $r;
    }

    $aoc = (1 / ($n + 1)) * ($sum);

    return $aoc;
}

// Mean F Score
function mfs($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    $p = precision($testinglines, $solutionlines, $predictionstart, $predictionend, $set);
    $r = recall($testinglines, $solutionlines, $predictionstart, $predictionend, $set);

    return 2 * ($p * $r) / ($p + $r);
}

// Mean Consequential Error
function mce($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;
    $fpnum = 0;
    $tnnum = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if ($solutioncolumns[$j] != $testcolumns[$j]) {
                $sum++;
            }
            $n++;
        }
    }

    $avg = $sum / $n;

    return $avg;
}

// Mean Average Precision at n
function mapn($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);
    $k = 10;

    if ($n > $k) {
        $n = $k;
    }

    $score = 0;
    $num_hits = 0;

    $actual = array();

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $actual[$key][$j] = $testcolumns[$j];
        }
    }

    $i = 1;
    foreach ($set as $set_num => $key) {
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if (in_array($solutioncolumns[$j], $actual)) {
                $num_hits++;
                $score += ($num_hits / $i) * (1 / $n);
            }
        }
        $i++;
    }

    return $score;
}

// Multi Class Log Loss
function mcll($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    $eps = 0.00001;

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $sum += log(max(min($solutioncolumns[$j],$eps),$eps));
            $n++;
        }

    }

    $avg = $sum / $n;

    return -$avg;
}

// Hamming Loss
function hl($testingfile, $solutionfile, $predictionstart, $predictionend, $set) {
    $testlines = file($testingfile);
    $solutionlines = file($solutionfile);

    //$count = count($testlines);

    $n = 0;
    $sum = 0;
    $avg = 0;
    $num = 0;
    $xor = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);
        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            $num++;

            if ($solutioncolumns[$j] != $testcolumns[$j]) {
                $xor++;
            }
        }
        $sum = $sum + ($xor / $num);
        $num = 0;
        $xor = 0;
        $n++;
    }

    $avg = $sum / $n;

    return $avg;
}

// Recall, true positive rate
function recall($testinglines, $solutionlines, $predictionstart, $predictionend, $set) {
    //$count = count($testlines);

    $tpnum = 0;
    $fnnum = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if ($solutioncolumns[$j] == 1 || $testcolumns[$j] == 1) {
                $tpnum++;
            }
            else if ($solutioncolumns[$j] == 0 || $testcolumns[$j] == 1) {
                $fnnum++;
            }
        }
    }

    return $tpnum / ($tpnum + $fnnum);
}

// Precision
function precision($testinglines, $solutionlines, $predictionstart, $predictionend, $set) {
    //$count = count($testlines);

    $fpnum = 0;
    $tpnum = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if ($solutioncolumns[$j] == 1 && $testcolumns[$j] == 0) {
                $fpnum++;
            }
            else if ($solutioncolumns[$j] == 1 && $testcolumns[$j] == 1) {
                $tpnum++;
            }
        }
    }

    return $tpnum / ($tpnum + $fpnum);
}

// False positive rate
function fprate($testinglines, $solutionlines, $predictionstart, $predictionend, $set) {
    //$count = count($testlines);

    $fpnum = 0;
    $tnnum = 0;

    foreach ($set as $set_num => $key) {
        $testcolumns = explode(",", $testlines[$key]);
        $solutioncolumns = explode(",", $solutionlines[$key]);

        for ($j = $predictionstart; $j <= $predictionend; $j++) {
            if ($solutioncolumns[$j] == 1 && $testcolumns[$j] == 0) {
                $fpnum++;
            }
            else if ($solutioncolumns[$j] == 0 && $testcolumns[$j] == 0) {
                $tnnum++;
            }
        }
    }

    return $fpnum / ($fpnum + $tnnum);
}
