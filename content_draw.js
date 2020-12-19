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
