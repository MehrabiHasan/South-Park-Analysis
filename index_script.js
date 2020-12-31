
    function getWidth(text, fontSize, fontFace) {
        var canvas = document.createElement('canvas'),
          context = canvas.getContext('2d');
        context.font = fontSize + 'px ' + fontFace;
        return context.measureText(text).width;
    }

    function setSummary(p_episode, p_season) {
      $.ajax({
        url: 'ajax_functions.php',
        type: 'POST',
        data: {
          'action': 'getQuery',
          'season': p_season,
          'episode': p_episode
        },
        success: function(rv) {
          let obj = JSON.parse(rv);
          $("#num_elements").html(obj[0][0]);
          $("#num_connections").html(obj[0][1]);
          $("#avg_connections").html(obj[0][2]);
          $("#max_connections").html(obj[0][3]);
          $("#min_connections").html(obj[0][4]);
        }
      });
    }

    // PRE: p_season is a season number and p_episode is an episode number in that season
    // POST: does an ajax request to get the occurences of names in that season
    function getCleanedNames(p_season, p_episode) {
      $.ajax({
        url: 'ajax_functions.php',
        type: 'POST',
        data: {
          'action': 'getCleanedNames',
          'season': p_season,
          'episode': p_episode
        },
        success: function(rv) {
          let data = [];
          chart = JSON.parse(rv);
          let left_margin = 5;
          $.each(chart, function (name, num) {
            data.push({"name": name, "value": num,});
            let tw = getWidth(name, 16, "Arial")
            left_margin = tw > left_margin? tw: left_margin;
          });

          $("#graphic").html("");

          //sort bars based on value
          data = data.sort(function (a, b) {
              return d3.ascending(a.value, b.value);
          })

          //set up svg using margin conventions - we'll need plenty of room on the left for labels
          var margin = {
              top: 15,
              right: 25,
              bottom: 15,
              left: left_margin
          };

          var width = 960 - margin.left - margin.right,
              height = 1200 - margin.top - margin.bottom;

          var svg = d3.select("#graphic").append("svg")
              .attr("width", width + margin.left + margin.right)
              .attr("height", height + margin.top + margin.bottom)
              .append("g")
              .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

          var x = d3.scale.linear()
              .range([0, width])
              .domain([0, d3.max(data, function (d) {
                  return d.value;
              })]);

          var y = d3.scale.ordinal()
              .rangeRoundBands([height, 0], .1)
              .domain(data.map(function (d) {
                  return d.name;
              }));

          //make y axis to show bar names
          var yAxis = d3.svg.axis()
              .scale(y)
              //no tick marks
              .tickSize(0)
              .orient("left")

          var gy = svg.append("g")
              .attr("class", "y axis")
              .call(yAxis)

          var bars = svg.selectAll(".bar")
              .data(data)
              .enter()
              .append("g")

          //append rects
          bars.append("rect")
              .attr("class", "bar")
              .attr("y", function (d) {
                  return y(d.name);
              })
              .attr("height", y.rangeBand())
              .attr("x", 0)
              .attr("width", function (d) {
                  return x(d.value);
              });

          //add a value label to the right of each bar
          bars.append("text")
              .attr("class", "label")
              //y position of the label is halfway down the bar
              .attr("y", function (d) {
                  return y(d.name) + y.rangeBand() / 2 + 4;
              })
              //x position is 3 pixels to the right of the bar
              .attr("x", function (d) {
                  return x(d.value) + 3;
              })
              .text(function (d) {
                  return d.value;
              });
        }
      });
    }

    // PRE: p_season is a season number and p_episode is an episode number in that season
    // POST: does an ajax request to get the mean, std, and var from sediment_grouping.csv
    function getSetimentGrouping(p_season, p_episode) {
      $.ajax({
        url: 'ajax_functions.php',
        type: 'POST',
        data: {
          'action': 'getSetimentGrouping',
          'season': p_season,
          'episode': p_episode
        },
        success: function(rv) {
          let obj = JSON.parse(rv);
          $("#sentiment-display").html(`<td>${obj[2]}</td><td>${obj[3]}</td><td>${obj[4]}</td>`);
        }
      });
    }

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
      getSetimentGrouping(season, episode);
      getCleanedNames(season, episode);
    });

    $(document).on("click", "#reload", function() {
      setSummary(episode, season);
    });

    $(document).ready(function() {
      setSummary(episode, season);
    });
