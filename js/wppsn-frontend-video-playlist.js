function play(id, div_id) {
  let container = document.getElementById("playlist_container");

  container.innerHTML =
    '<iframe id="videos_playlist" class="responsive-iframe" src="https://alpha.preprod.alchemyasp.com/embed/?url=' +
    id +
    '" frameborder="0" allowfullscreen="" webkitallowfullscreen="" mozallowfullscree=""></iframe></div></div>';

  let plist = document.getElementsByClassName("plist");

  for (let i = 0; i < plist.length; i++) {
    plist[i].style.backgroundColor = "white";
  }

  document.getElementById(div_id).style.backgroundColor = "rgb(22 22 23 / 7%)";
}
function gif(id, visible) {
  if (visible == 1) {
    let gif_img = document.getElementById("gif_" + id).value;

    document.getElementById("img_" + id).src = gif_img;
  } else {
    let web_img = document.getElementById("web_img_" + id).value;

    document.getElementById("img_" + id).src = web_img;
  }
}
