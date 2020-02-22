<?php
// Complete the roadsAndLibraries function below.
function getNeighbors(int $city, array &$graph):array {
    return $graph[$city] ?? [];
}
function roadsAndLibraries($n, $c_lib, $c_road, $cities) {
    // todo return cities multiply by library cost if library cost <= roud cost
    if ($c_lib <= $c_road) {
        return $c_lib * $n;
    }

    $graph = [];
    foreach($cities as $roud) {
        $start = $roud[0];
        $end = $roud[1];
        if (!isset($graph[$start])) {
            $graph[$start] = [];
        }
        if (!isset($graph[$end])) {
            $graph[$end] = [];
        }
        $graph[$start][] = $end;
        $graph[$end][] = $start;
    }

    $connected = [];
    $connected = array_fill(1, $n, false);

    $library = 0;
    $roud = 0;
    $count = 0;


    for($city = 1; $city <= $n; $city++) {
        if ($connected[$city]) {
            continue;
        }
        $connected[$city] = true;
        $library++;
        $count++;

        $neighbors = getNeighbors($city, $graph);
        while(!empty($neighbors)) {
            $neighbor = array_shift($neighbors);
            if ($connected[$neighbor]) {
                continue;
            }
            $neighbors = array_merge($neighbors, getNeighbors($neighbor, $graph));

            $connected[$neighbor] = true;
            $roud++;
            $count++;
            if ($count === $n) {
                break 2;
            }
        }
    }
    return $roud * $c_road + $library * $c_lib;

}

$fptr = fopen("php://stdout", 'w');

$stdin = fopen("./test1.txt", 'r');

fscanf($stdin, "%d\n", $q);

for ($q_itr = 0; $q_itr < $q; $q_itr++) {
    fscanf($stdin, "%[^\n]", $nmC_libC_road_temp);
    $nmC_libC_road = explode(' ', $nmC_libC_road_temp);

    $n = (int)$nmC_libC_road[0];

    $m = (int)$nmC_libC_road[1];

    $c_lib = (int)$nmC_libC_road[2];

    $c_road = (int)$nmC_libC_road[3];

    $cities = array();

    for ($i = 0; $i < $m; $i++) {
        fscanf($stdin, "%[^\n]", $cities_temp);
        $cities[] = array_map('intval', preg_split('/ /', $cities_temp, -1, PREG_SPLIT_NO_EMPTY));
    }

    $result = roadsAndLibraries($n, $c_lib, $c_road, $cities);

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
