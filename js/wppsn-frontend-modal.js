function openModel(img) {
  console.log(img);
  img = document.getElementById(img);

  let modal = document.getElementById("myModal_" + img.id);

  let modalImg = document.getElementById("img_" + img.id);

  let captionText = document.getElementById("caption_" + img.id);

  modal.style.display = "block";
  modalImg.src = img.rel ? img.rel : img.src;
  captionText.innerHTML = img.name;

  // Get the <span> element that closes the modal
  let spans = document.getElementsByClassName("close");

  // When the user clicks on <span> (x), close the modal

  for (let i = 0; i < spans.length; i++) {
    spans[i].onclick = function () {
      modal.style.display = "none";
    };
  }
}

function plusSlides(slide, slide_to) {
  document.getElementById("myModal_" + slide).style.display = "none";
  if (slide_to == 1) {
    //Next
    slide = parseInt(slide) + 1;
  } else {
    //Prev
    if (slide != 1) {
      slide = parseInt(slide) - 1;
    }
  }
  slide = !document.getElementById(slide) ? 1 : slide; //loop if there is no next id
  openModel(slide);
}

setTimeout(function () {
  var c = document.getElementsByClassName("middle");
  if (window.innerWidth < 700) {
    var els = [].slice.apply(document.getElementsByClassName("middle"));
    for (var i = 0; i < els.length; i++) {
      els[i].className = els[i].className.replace(/ *\bmiddle\b/g, "middle2");
    }

    var els = [].slice.apply(document.getElementsByClassName("gallery-item"));
    for (var i = 0; i < els.length; i++) {
      els[i].className = els[i].className.replace(
        / *\gallery-item\b/g,
        "gallery-item2"
      );
    }
  }
}, 200);
