import re
import pathlib

all_dart_text = ""
for child in pathlib.Path(r'f:\printer\Flutter-TDD-Clean-Architecture-E-Commerce-App\lib').rglob('*.dart'):
    all_dart_text += child.read_text(encoding='utf-8', errors='ignore') + "\n"

keys = set(re.findall(r"['\"](sylius\.[^'\"]+)['\"]", all_dart_text))

print(f"Found sylius keys: {keys}")
