#!/bin/bash
# Script to download Khmer fonts for hotel_mis.com
# Run this script to automatically download font files

FONT_DIR="$(dirname "$0")"

echo "📦 Downloading Khmer Fonts..."

# Download Hanuman Font
echo "⬇️  Downloading Hanuman font..."
cd "$FONT_DIR/hanuman"
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/hanuman/Hanuman-Thin.ttf" -O Hanuman-Thin.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/hanuman/Hanuman-Light.ttf" -O Hanuman-Light.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/hanuman/Hanuman-Regular.ttf" -O Hanuman-Regular.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/hanuman/Hanuman-Bold.ttf" -O Hanuman-Bold.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/hanuman/Hanuman-Black.ttf" -O Hanuman-Black.ttf
echo "✅ Hanuman font downloaded"

# Download Kantumruy Pro Font
echo "⬇️  Downloading Kantumruy Pro font..."
cd "$FONT_DIR/kantumruy-pro"
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-Thin.ttf" -O KantumruyPro-Thin.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-ExtraLight.ttf" -O KantumruyPro-ExtraLight.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-Light.ttf" -O KantumruyPro-Light.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-Regular.ttf" -O KantumruyPro-Regular.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-Medium.ttf" -O KantumruyPro-Medium.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-SemiBold.ttf" -O KantumruyPro-SemiBold.ttf
wget -q --show-progress "https://github.com/google/fonts/raw/main/ofl/kantumruypro/KantumruyPro-Bold.ttf" -O KantumruyPro-Bold.ttf
echo "✅ Kantumruy Pro font downloaded"

echo ""
echo "🎉 All Khmer fonts downloaded successfully!"
echo "📂 Fonts location: $FONT_DIR"
