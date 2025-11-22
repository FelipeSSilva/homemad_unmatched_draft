<?php require_once 'boot.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Unmatched Draft</title>
    <link rel="stylesheet" href="<?= url('css/style.css?v=' . $_ENV['VERSION']) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,600;1,300&family=Staatliches&display=swap" rel="stylesheet">

    <meta property="og:image" content="<?= url('og.png') ?>" />

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#1a2266">
    <meta name="msapplication-TileColor" content="#fdfcf8">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <div class="container">
        <div class="content-wrap">
            <h1>Home Draft Generator</h1>
            <h2>for Unmatched</h2>
        </div>

        <div id="tabs">
            <nav>
                <div class="content-wrap">
                    <div class="left">
                        <a class="active" href="#generator">Generator</a>
                    </div>
                    <div class="right">
                        <a href="#statistics">Statistics</a>
                    </div>
                </div>
            </nav>

            <div class="tab active" id="generator">
                <form id="generate-form" action="generate.php" method="post">
                    <div class="section">
                        <div class="content-wrap">
                            <div class="header">
                                <div>
                                    <h3>Players</h3>
                                </div>
                                <p class="help">
                                    Choose the number of players and fill in their names. Draft order will be randomised unless otherwise specified (in the advanced settings below).
                                </p>
                            </div>
                            <div class="content">
                                <div class="input">
                                    <label for="num_players">
                                        Number of players
                                    </label>
                                    <input type="number" name="num_players" id="num_players" value="4" min="2" max="6" required />
                                </div>

                                <div class="players_inputs">
                                    <div class="alliance_team team_a">
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Amy") ?>" />
                                        </div>
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Ben") ?>" />
                                        </div>
                                    </div>
                                    <div class="alliance_team team_b">
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Charlie") ?>" />
                                        </div>
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Desmond") ?>" />
                                        </div>
                                    </div>
                                    <div class="alliance_team team_c">
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Esther") ?>" />
                                        </div>
                                        <div class="input player">
                                            <input type="text" placeholder="Player Name" name="player[]" value="<?= e(get('debug', false), "Frank") ?>" />
                                        </div>
                                    </div>
                                    <div class="alliance_team team_d">
                                        <div class="input player">
                                            <input type="text" placeholder="Player NameD1" name="player[]" />
                                        </div>
                                        <div class="input player">
                                            <input type="text" placeholder="Player NameD2" name="player[]" />
                                        </div>
                                    </div>
                                </div>

                                <a class="btn small" href="#" id="add-player" title="Add Player">+</a>
                            </div>
                        </div>


                    </div>

                    <div class="section">

                        <div class="content-wrap">
                            <div class="header">
                                <h3>Settings</h3>
                            </div>
                            <div class="content">

                                <div class="input">
                                    <label for="game_name">
                                        Game Name
                                    </label>
                                    <input type="text" placeholder="Game Name" maxlength="100" name="game_name" id="game_name" />

                                    <span class="help">
                                        Optional. To help you remember which draft is which, because after two or three drafts that gets confusing. If you leave this blank it will generate something random like "Operation Glorious Drama".
                                    </span>
                                </div>

                                <h4>Sets</h4>
                                <div class="input">
                                     <span class="help">
                                            Select the sets that will be considered in the draft.<br /><br />
                                            <strong>Note: It is necessary to select enough sets to reach 4 heroes per player.</strong>
                                        </span>

                                    <span class="help">
                                            <a href="#" id="select-all">Select All</a> / <a href="#" id="deselect-all">Deselect All</a>
                                        </span>

                                    <div class="input-group factions">
                                        <?php require_once 'sets.php'; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input center content-wrap">
                        <p id="error">
                        </p>

                        <p>
                            <button type="submit" id="submit">Generate</button>
                        </p>
                    </div>
                </form>
            </div>
            <?php require_once 'statistics.php'; ?>
        </div>
    </div>

    <div class="overlay" id="loading">
        Loading. Please wait.<br />
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="0" fill="none" stroke="#fefcf8" stroke-width="2">
                <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="0s"></animate>
                <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="0s"></animate>
            </circle>
            <circle cx="50" cy="50" r="0" fill="none" stroke="#fefcf8" stroke-width="2">
                <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5s"></animate>
                <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5s"></animate>
            </circle>
        </svg>
    </div>

    <script>
        window.routes = {
            "generate": "<?= url('generate.php') ?>"
        }
    </script>

    <script src="<?= url('js/vendor.js?v=' . $_ENV['VERSION']) ?>"></script>
    <script src="<?= url('js/main.js?v=' . $_ENV['VERSION']) ?>"></script>
</body>

</html>