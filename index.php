<?php
	require __DIR__ . '/vendor/autoload.php';
  $json = file_get_contents("episode_data.json");
?>

<head>
  <!-- Set HTML document attributes -->
  <title>South Park Social Network</title>

  <!-- Link CSS files -->
  <link rel="stylesheet" href="stylesheet.css" type="text/css">

  <!-- Link javascript files -->
  <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=oO8KeTII6I8csMAXvfY_vEcf9-9_rnjq38tWW1GSpIzm_SKpeAndKoWUhZnX-5AqQQXeXc9v5UrGBwQj9a0PwbUYdZS0xhyd-VQvaqXaVGH6wYyC5mqsYFtDh2KoacdhVZkvpkS-LwAOoZR47bF7VA" charset="UTF-8"></script><link rel="stylesheet" crossorigin="anonymous" href="https://gc.kis.v2.scr.kaspersky-labs.com/E3E8934C-235A-4B0E-825A-35A08381A191/abn/main.css?attr=aHR0cHM6Ly9jZG4uZGlzY29yZGFwcC5jb20vYXR0YWNobWVudHMvNzE3NDQ3NzgyMTU5NDgyOTMwLzc3ODcyNDc3NDk1MTUxODIzOS9uZW92aXMuaHRtbA"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
  <script src="index_script.js" defer></script>

  <!-- content -->
  <script type="text/javascript" src="https://rawgit.com/johnymontana/neovis.js/master/dist/neovis.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="content_draw.js" type="text/javascript"></script>
  <script src="cypher.js" defer></script>

</head>
<body onload="draw()">
  <!-- This is the title and description of the webpage -->
  <div id="title-bar">
    <h1>South Park Social Network Analysis</h1>
    <p>This is an application that can create graphic diagram of Social Network for each southpark episode</p>
  </div>
  <div class="splitscreen">
    <!-- This is the LEFT side of the application -->
    <div class="left">
      <div id="viz">
      </div>
			<div>
				Number of elements: <span id="num_elements"></span>
				Number of connections: <span id="num_connections"></span>
				Average Degree: <span id="avg_connections"></span>
				Maximum Degree: <span id="max_connections"></span>
				Minimum Degree: <span id="min_connections"></span>
			</div>
    </div>
    <!-- This is the RIGHT side of the application -->
    <div class="right">
      <!-- content -->
      <div>
        Cypher query:
      </div>
      <div>
        <select id="season-select">
          <option value="" hidden disabled selected>-Select a Season-</option>
          <option value="1-13">Season 1</option>
          <option value="2-18">Season 2</option>
          <option value="3-17">Season 3</option>
          <option value="4-17">Season 4</option>
          <option value="5-14">Season 5</option>
          <option value="6-17">Season 6</option>
          <option value="7-15">Season 7</option>
          <option value="8-14">Season 8</option>
          <option value="9-14">Season 9</option>
          <option value="10-14">Season 10</option>
          <option value="11-14">Season 11</option>
          <option value="12-14">Season 12</option>
          <option value="13-14">Season 13</option>
          <option value="14-14">Season 14</option>
          <option value="15-14">Season 15</option>
          <option value="16-14">Season 16</option>
          <option value="17-10">Season 17</option>
          <option value="18-10">Season 18</option>
          <option value="19-10">Season 19</option>
          <option value="20-10">Season 20</option>
          <option value="21-10">Season 21</option>
          <option value="22-10">Season 22</option>
          <option value="23-10">Season 23</option>
        </select>

        <select id="episode-select" hidden>
        </select>

        <select id="Minimum">
            <option value = "" disabled selected> Minimum</option>
            <option value = 0> 0</option>
            <option value = 1> 1</option>
            <option value = 2> 2</option>
            <option value = 3> 3</option>
            <option value = 4> 4</option>
            <option value = 5> 5</option>
            <option value = 6> 6</option>
            <option value = 7> 7</option>
            <option value = 8> 8</option>
            <option value = 9> 9</option>
            <option value = 10> 10</option>
        </select>
	     </div>

       <textarea rows="4" cols=50 id="cypher"></textarea><br>
       <input type="submit" value="Submit" id="reload"/>
       <input type="submit" value="Stabilize" id="stabilize"/>

		   <div class="Episode_Desc">
         <h1 id="title"> Episode Name</h1>
         <h3> Character List</h3>
         <ul id="characters">
           <li>"Eric Cartman"</li>
           <li>"Stan Marsh"</li>
         </ul>
         <p id="description"> Episode Description</p>
       </div>

       <div id="graphic-container">
         <div id="graphic"></div>
       </div>

       <div id="sentiment">
         <h3 class="sentiment">Sentiment</h3>
         <table>
           <tr>
             <th>Mean</th>
             <th>St. Dev</th>
             <th>Var</th>
           </tr>
           <tr id="sentiment-display">

           </tr>
         </table>
       </div>
     </div>
   </div>
   <!-- This creates the json text for js to grab -->
   <div id="json" hidden>
       <?php
         echo $json;
       ?>
   </div>

 </body>
