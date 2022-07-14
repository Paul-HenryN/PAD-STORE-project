/**
 * command.js
 *
 * @author Paul-henry NGOUNOU
 */

//Command objects
let quantities = {};
let prices = {};
let categories = {};

//DOM
const commandList = document.querySelector(".command__list");
const priceContainer =
  document.querySelector(".command__pricing").firstElementChild;
let cardBodies = document.querySelectorAll(".card-body");
const submitBtn = document.querySelector(".command__btn");
const catalogGrid = document.querySelector(".catalog__grid");


//Functions
function updateCards() {
  cardBodies = document.querySelectorAll(".card-body");
  cardBodies.forEach((cardBody) => {
    const articleName = cardBody.firstElementChild.innerHTML.toString();
    const articlePrice =
      cardBody.firstElementChild.nextElementSibling.innerHTML.split(" ")[0];
    const addBtn = cardBody.lastElementChild;

    console.log(articleName);

    prices[articleName] = parseInt(articlePrice);
    
    addBtn.addEventListener("click", () => {
      addToCommand(articleName);
      updateCommandList();
      updatePrice();
      updateBtnState();
      console.log(quantities);
    });
  });
}

function addToCommand(articleName) {
  if (quantities[articleName]) {
    quantities[articleName]++;
  } else {
    quantities[articleName] = 1;
  }

  localStorage.setItem("quantities", JSON.stringify(quantities));
}

function removeFromCommand(articleName) {
  delete quantities[articleName];
}

function updateCommandList() {
  commandList.innerHTML = "";

  for (let article in quantities) {
    const newLi = document.createElement("li");
    const newTextNode = document.createTextNode(article);
    const newSpan = document.createElement("span");
    const newDelIcon = document.createElement("button");

    newDelIcon.classList.add("ps-btn");
    newDelIcon.innerHTML = "&times;";

    newDelIcon.addEventListener("click", () => {
      removeFromCommand(article);
      updateCommandList();
      updatePrice();
      updateBtnState();
    });

    newSpan.classList.add("item__quantity");
    newSpan.innerHTML = quantities[article];
    newLi.classList.add("command__list-item");

    newLi.appendChild(newSpan);
    newLi.appendChild(newTextNode);
    newLi.appendChild(newDelIcon);

    commandList.appendChild(newLi);
  }
}


function updateBtnState() {
  if(Object.keys(quantities).length > 0){
    submitBtn.classList.remove("ps-btn-disabled");
    return;
  }

  submitBtn.classList.add("ps-btn-disabled");
}

function updatePrice() {
  let totalAmount = 0;

  for (article in quantities) {
    totalAmount += quantities[article] * prices[article];
  }

  priceContainer.innerHTML = totalAmount;
}


function main() {
  updateCards();
  updateCommandList();
  updatePrice();
  updateBtnState();


  submitBtn.addEventListener("click", () => {
    $.ajax({
      url: "http://localhost:8000/command/store",
      data: JSON.stringify(quantities),
      method: "POST",
      headers: { "X-CSRF-TOKEN": localStorage.getItem("token") },
      success: (result, status, xhr) => {
        console.log(quantities);
        localStorage.removeItem("quantities");
        quantities = {};
        updateCommandList();
        updatePrice();
      },

      error: (xhr, status, error) => {
        console.error(error, status, xhr);
      },
    });
  });
}

main();
