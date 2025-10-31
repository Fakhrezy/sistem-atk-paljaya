function Invoke-Sail {
    param(
        [Parameter(ValueFromRemainingArguments)]
        [string[]]$Arguments
    )

    docker compose up -d
    if ($Arguments) {
        $containerName = "atk-app"
        docker exec -it $containerName php artisan $Arguments
    }
}

Set-Alias -Name sail -Value Invoke-Sail
