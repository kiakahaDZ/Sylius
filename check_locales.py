import re
import pathlib

# Read app localizations
with open('f:\printer\Flutter-TDD-Clean-Architecture-E-Commerce-App\lib\core\localization\app_localizations.dart', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract the 'en', 'fr', 'ar' maps
en_match = re.search(r"'en': \{([^}]+)\}", content)
fr_match = re.search(r"'fr': \{([^}]+)\}", content)
ar_match = re.search(r"'ar': \{([^}]+)\}", content)

def extract_keys(dict_content):
    if not dict_content: return set()
    return set(re.findall(r"'(.*?)':", dict_content))

en_keys = extract_keys(en_match.group(1))
fr_keys = extract_keys(fr_match.group(1))
ar_keys = extract_keys(ar_match.group(1))

all_keys = en_keys | fr_keys | ar_keys

# Find used keys in dart files
all_dart_text = ""
for child in pathlib.Path('f:\printer\Flutter-TDD-Clean-Architecture-E-Commerce-App\lib').rglob('*.dart'):
    all_dart_text += child.read_text(encoding='utf-8', errors='ignore') + "\n"

# Look for translate('...') or translate("...")
used_keys = set(re.findall(r"translate\(['\"](.*?)['\"]", all_dart_text))

missing_in_en = used_keys - en_keys
missing_in_fr = used_keys - fr_keys
missing_in_ar = used_keys - ar_keys

print(f"Missing in EN: {missing_in_en}")
print(f"Missing in FR: {missing_in_fr}")
print(f"Missing in AR: {missing_in_ar}")
print(f"Missing in EN (from FR/AR): {all_keys - en_keys}")
print(f"Missing in FR (from EN/AR): {all_keys - fr_keys}")
print(f"Missing in AR (from EN/FR): {all_keys - ar_keys}")
