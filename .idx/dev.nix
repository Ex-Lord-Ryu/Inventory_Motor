{pkgs}: {
  channel = "stable-24.05";
  packages = [
    pkgs.php82
    pkgs.php82Packages.composer
    pkgs.nodejs_20
  ];
  idx.extensions = [
    "svelte.svelte-vscode"
    "vue.volar"
  ];
  idx.workspace = {
    onCreate = {
      npm-install = "npm install";
      composer-install = "composer install";
    };
  };
  idx.previews = {
    previews = {
      web = {
        command = ["php" "artisan" "serve" "--port" "$PORT" "--host" "0.0.0.0"];
        manager = "web";
      };
    };
  };
}