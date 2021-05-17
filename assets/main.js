const searchInput = document.getElementById("search_input");
const searchBtn = document.getElementById("search_btn");
const searchResults = document.getElementById("search_results");
const rootUrl = pokeData.root_url;
let previousValue;
let typingTimer;
let isSpinnerVisible = false;
let searchTimer;

searchInput.addEventListener("keyup", () => {
  if (searchInput.value !== previousValue) {
    clearTimeout(typingTimer);
    clearTimeout(searchTimer);
    if (searchInput.value) {
      typingTimer = setTimeout(showResults, 500);
      if (!isSpinnerVisible) {
        searchResults.innerHTML = `<div class="sorry_card"><div class="spinner"></div></div>`;
        isSpinnerVisible = true;
      }
    } else {
      searchResults.innerHTML = "";
      isSpinnerVisible = false;
    }
  }
  previousValue = searchInput.value;
});

const showResults = async () => {
  searchResults.classList.remove("hide");
  searchResults.classList.add("show");
  console.log("show results was called");
  let searchTerm = searchInput.value;
  let data = await fetch(
    `${rootUrl}/wp-json/pokepress/v1/search?term=${searchTerm}`
  );
  let results = await data.json();
  isSpinnerVisible = false;
  if (results.length) {
    let items = "";
    items += results
      .map(
        (pokemon) =>
          `
        <a href="${pokemon.permalink}" class="page_link">
                    <div class="search_result_card">
                        <div class="search_pokmemon_icon_div">
                            <img class="search_pokemon_icon" src="${pokemon.icon}" alt="${pokemon.title}">
                        </div>
                        <h4 class="search_pokemon_name">${pokemon.title} (${pokemon.id})</h4>
                    </div>
        </a>
                `
      )
      .join("");
    searchResults.innerHTML = items;
    searchTimer = setTimeout(() => {
      searchResults.innerHTML = "";
      searchResults.classList.add("hide");
      searchResults.classList.remove("show");
      searchInput.value = "";
    }, 10000);
  } else {
    searchResults.innerHTML = `<div class="sorry_card"><h4>Sorry, no results found !</h4></div>`;
    searchTimer = setTimeout(() => {
      searchResults.innerHTML = "";
      searchResults.classList.add("hide");
      searchResults.classList.remove("show");
    }, 5000);
  }
};

searchBtn.addEventListener("click", () => {
  if (searchInput.value) {
    showResults();
  }
});
