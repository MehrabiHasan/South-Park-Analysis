<?php
  require __DIR__ . '/vendor/autoload.php';

  if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    switch($action) {
        case 'getCleanedNames' :
          echo getCleanedNames();
          break;
        case 'getSetimentGrouping':
          echo getSetimentGrouping();
          break;
        case 'getQuery':
          echo getQuery();
          break;
    }
  }

  function getCleanedNames(){
    // Get data from ajax
    $season = $_POST['season'];
    $episode = $_POST['episode'];
    unset($_POST['season']);
    unset($_POST['episode']);

    // Create Dictionary of character names to appearence in excel file

    // Parse CSV File
    $chart = [];
    $csv_string = file_get_contents('Cleaned Names.csv');
    $csv_strings = explode("\n", $csv_string);
    foreach ($csv_strings as $csv_str) {
      $csv = str_getcsv($csv_str);
      $chart[$csv[0]][$csv[1]][$csv[2]] = (isset($chart[$csv[0]][$csv[1]][$csv[2]]))? $chart[$csv[0]][$csv[1]][$csv[2]]+1: 1;
    }
    return json_encode($chart[$season][$episode]);
  }

  function getSetimentGrouping() {
    // Get data from ajax
    $season = $_POST['season'];
    $episode = $_POST['episode'];
    unset($_POST['season']);
    unset($_POST['episode']);

    // Parse CSV File
    $chart = [];
    $csv_string = file_get_contents('sentiment_grouping.csv');
    $csv_strings = explode("\n", $csv_string);
    foreach ($csv_strings as $csv_str) {
      $csv = str_getcsv($csv_str);
      $chart[$csv[0]][$csv[1]] = $csv;
    }

    // return this season and episode
    return json_encode($chart[$season][$episode]);
  }

  function getQuery() {
    // Get data from ajax
    $season = $_POST['season'];
    $episode = $_POST['episode'];
    unset($_POST['season']);
    unset($_POST['episode']);

    $bolt = new \Bolt\Bolt(new \Bolt\connection\StreamSocket());
    $bolt->init("New SouthPark", "mehrabi", "mehrabi");

    // Query
    $query_string =
      "MATCH (c1:Character)-[r:Interaction]->(c2:Character)
       WHERE r.Episode = $episode and r.Season = $season
       WITH c1, count(*) as degree, type(r) as rel_type
       RETURN count(c1) as element, ToInteger(round(sum(degree))) as rels, ToInteger(round(avg(degree))) as avg, ToInteger(round(max(degree))) as max, ToInteger(round(min(degree))) as min";
    $bolt->run($query_string);
    $rows = $bolt->pull();
    return json_encode($rows);
  }

?>
