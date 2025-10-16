<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pesquisa DMREDE</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <!-- Barra de Pesquisa Principal -->
    <div class="search-bar">
      <input
        type="text"
        id="searchInput"
        placeholder="Digite sua pesquisa..."
        autocomplete="off"
      />
      <button id="searchBtn">🔍</button>
      <button id="voiceBtn" title="Pesquisar por voz">🎤</button>
    </div>

    <!-- Janela de Resultados -->
    <div id="resultsWindow" class="results-window hidden">
      <div class="results-header">
        <input
          type="text"
          id="resultSearchInput"
          placeholder="Nova pesquisa..."
          autocomplete="off"
        />
        <button id="newSearchBtn">🔎 Nova Busca</button>
      </div>
      <div id="resultsList" class="results-list"></div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>