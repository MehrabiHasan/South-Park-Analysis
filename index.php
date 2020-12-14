<?php
  $json = file_get_contents("episode_data.json");
?>

<head>
  <title>South Park Social Network</title>
  <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=oO8KeTII6I8csMAXvfY_vEcf9-9_rnjq38tWW1GSpIzm_SKpeAndKoWUhZnX-5AqQQXeXc9v5UrGBwQj9a0PwbUYdZS0xhyd-VQvaqXaVGH6wYyC5mqsYFtDh2KoacdhVZkvpkS-LwAOoZR47bF7VA" charset="UTF-8"></script><link rel="stylesheet" crossorigin="anonymous" href="https://gc.kis.v2.scr.kaspersky-labs.com/E3E8934C-235A-4B0E-825A-35A08381A191/abn/main.css?attr=aHR0cHM6Ly9jZG4uZGlzY29yZGFwcC5jb20vYXR0YWNobWVudHMvNzE3NDQ3NzgyMTU5NDgyOTMwLzc3ODcyNDc3NDk1MTUxODIzOS9uZW92aXMuaHRtbA"/>
  <style type="text/css">
    html, body {
      font: 16pt arial;
    }

    #viz {
      width: 900px;
      height: 700px;
      border: 1px solid lightgray;
      font: 22pt arial;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    let season = 1;
    let episode = 1;
    let min = 0;
    $(document).on("change", "#season-select", function() {
      let option = $(this).val().split("-");
      season = option[0];
      let episodes = parseInt(option[1]);
      let episode_select_html = `<option value="" hidden disabled selected>-Select an Episode-</option>`;
      for (let i = 0; i < episodes; i++){
        episode_select_html += `<option value="${i+1}">Episode ${i+1}</option>`
      }
      $("#episode-select").html(episode_select_html);
      $("#episode-select").removeAttr("hidden");
    });
    $(document).on("change", "#Minimum", function(){
      min = $(this).val();
      if ((min !== 0 || min !== "")) {
        $("#cypher").html(`MATCH p=(c1:Character)-[r:Interaction]->() WHERE r.Season = ${season} AND r.Episode = ${episode} AND r.Num > ${min} RETURN p`);
      } else {
        $("#cypher").html(`MATCH p=(c1:Character)-[r:Interaction]->() WHERE r.Season = ${season} AND r.Episode = ${episode} RETURN p`);
      }
    })
    $(document).on("change", "#episode-select", function(){
      episode = $(this).val();
      $("#cypher").html(`MATCH p=(c1:Character)-[r:Interaction]->() WHERE r.Season = ${season} AND r.Episode = ${episode} AND r.Num > ${min} RETURN p`);

      // Populate Episode Information
      let json_string = $("#json").html();
      let json = JSON.parse(json_string);
      $("#title").html(json[season][episode]["title"]);
      $("#description").html(json[season][episode]["description"]);
      let characters = (json[season][episode]["characters"]);
      let character_html = "";
      for (let i = 0; i < characters.length; i++){
        character_html += `<li>${characters[i]}</li>`
      }
      $("#characters").html(character_html);
    });
  </script>

  <h1>South Park Social Network Analysis</h1>
  <p>This is an application that can create graphic diagram of Social Network for each southpark episode</p>
  <style>
    .splitscreen {
      display:flex;
    }
    .splitscreen .left {
      flex: 1;
    }
    .splitscreen .right {
      flex: 1;
    }
    .Episode_Desc{
      height: 560px;
      overflow-y:auto;
    }
  </style>
  <div class="splitscreen">
    <div class="left">
      <!-- content -->
      <script type="text/javascript" src="https://rawgit.com/johnymontana/neovis.js/master/dist/neovis.js"></script>


      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

      <script type="text/javascript">
        // define config car
        // instantiate nodevis object
        // draw

        var viz;

        function draw() {
            var config = {
                container_id: "viz",
                server_url: "bolt://localhost:7687",
                server_user: "mehrabi",
                server_password: "mehrabi",
                labels:{
                    "Character": {
                        caption: "Name",
                        size: "pagerank",
                        community: "community_lp"
                    }
                },
                relationships: {
                    "Interaction": {
                        caption: false,
                        thickness: "Num"
                    }
                },
                initial_cypher: "MATCH p=(c1:`Character`)-[r:Interaction]->() WHERE r.Season = 1 AND r.Episode = 1 RETURN p"
            };

            viz = new NeoVis.default(config);
            viz.render();
            console.log(viz);

        }
    </script>
</head>
<body onload="draw()">
  <div id="viz">
    </div></div>
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
<input type="submit" value="Submit" id="reload">
<input type="submit" value="Stabilize" id="stabilize">

		<div class="Episode_Desc">
			<h1 id="title"> Episode Name</h1>
			<h3> Character List</h3>
			<ul id="characters">
				<li>"Eric Cartman"</li>
				<li>"Stan Marsh"</li>
			</ul>
			<p id="description"> Episode Description</p>
		</div>
</body>

<script>
	$("#reload").click(function() {

		var cypher = $("#cypher").val();

		if (cypher.length > 3) {
			viz.renderWithCypher(cypher);
		} else {
			console.log("reload");
			viz.reload();

		}

	});

	$("#stabilize").click(function() {
		viz.stabilize();
	})

</script>

    </div>
</div>

<div id="json" hidden>
    <?php
      echo $json;
    ?>
</div>
