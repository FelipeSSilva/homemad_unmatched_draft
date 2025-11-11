#!/bin/bash
set -e

ENV_FILE="/var/www/html/.env"

if [ ! -f "$ENV_FILE" ]; then
  echo "🔧 Gerando arquivo .env com variáveis de ambiente..."
  cat > "$ENV_FILE" <<EOL
URL=${URL}
ACCESS_KEY=${ACCESS_KEY}
ACCESS_SECRET=${ACCESS_SECRET}
BUCKET=${BUCKET}
REGION=${REGION}
STORAGE=${STORAGE}
STORAGE_PATH=${STORAGE_PATH}
VERSION=${VERSION}
EOL
  echo "✅ .env gerado com sucesso!"
else
  echo "ℹ️ .env já existe, não será sobrescrito."
fi

echo "Iniciando o Apache..."
exec "$@"
