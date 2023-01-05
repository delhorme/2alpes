/*
 * Galerie-LD JS - v1 - 2023-01-05
 * Copyright 2023, Delhorme Laurent
*/

window.onload = () => {
  for (let i of document.querySelectorAll(".gallery img")) {
    i.onclick = () => i.classList.toggle("full");
  }
};