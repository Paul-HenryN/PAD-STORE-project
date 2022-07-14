/**
 * PAD-STORE
 * live_search.js
 *
 * Live search module for searching into articles
 */

// DOM
const searchBar = document.getElementById("search_bar");
const catalog = document.querySelector(".catalog__grid");
const addBtn = document.querySelector(".btn-add");

function updateArticleList(foundArticles) {
  catalog.innerHTML = "";

  if (foundArticles.length == 0) {
    catalog.innerHTML = "<p>Aucun résultat</p>";
    return;
  }

  for (const article of foundArticles) {
    articleCard = !addBtn? 
    `
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">${article.name}</h5>
                    <p>Description de l'article</p>
                    <h6>${article.price} FCFA</h6>
                </div>
            </div>
        </div>
    `
      : 
    `
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">${article.name}</h5>
                    <h6>${article.price} FCFA</h6>
                    <button href="#" class="ps-btn ps-btn-circ ps-btn-primary btn-add">+</button>
                </div>
            </div>
        </div>
    `;

    catalog.innerHTML += articleCard;

    if(addBtn)
      updateCards();
  }
}

function searchArticles(searchToken, callback) {
  console.log("searching...");
  var xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      callback(JSON.parse(xhttp.response));
    }
  };

  let url =
    searchToken !== ""
      ? "/articles/search/" + searchToken
      : "/articles/search/all";

  xhttp.open("GET", url, true);
  xhttp.send();
}

function main() {
  searchBar.addEventListener("input", () => {
    searchArticles(searchBar.value, updateArticleList);
  });
}

main();
