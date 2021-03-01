function play(id) {
  let container = document.getElementById("playlist_container");

  container.innerHTML =
    '<iframe id="videos_playlist" class="responsive-iframe" src="https://alpha.preprod.alchemyasp.com/embed/?url=' +
    id +
    '" frameborder="0" allowfullscreen="" webkitallowfullscreen="" mozallowfullscree=""></iframe></div></div>';

  let plist = document.getElementsByClassName("plist");

  for (let i = 0; i < plist.length; i++) {
    plist[i].style.backgroundColor = "white";
  }

  document.getElementById(id).style.backgroundColor = "#f1f1f1";
}
