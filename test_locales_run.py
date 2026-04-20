import re
import pathlib

with open(r'f:\printer\Flutter-TDD-Clean-Architecture-E-Commerce-App\lib\core\localization\app_localizations.dart', 'r', encoding='utf-8') as f: 
    content = f.read()

en_match = re.search(r"'en': \{([^}]+)\}", content)
fr_match = re.search(r"'fr': \{([^}]+)\}", content)
ar_match = re.search(r"'ar': \{([^}]+)\}", content)

def extract_keys(dict_content):
    if not dict_content: return set()
    return set(re.findall(r"'(.*?)':", dict_content))

en_keys = extract_keys(en_match.group(1))
fr_keys = extract_keys(fr_match.group(1))
ar_keys = extract_keys(ar_match.group(1))

all_dart_text = ""
for child in pathlib.Path(r'f:\printer\Flutter-TDD-Clean-Architecture-E-Commerce-App\lib').rglob('*.dart'):
    all_dart_text += child.read_text(encoding='utf-8', errors='ignore') + "\n"

used_keys = set(re.findall(r"translate\(context,\s*['\"](.*?)['\"]", all_dart_text)) | set(re.findall(r"translate\(['\"](.*?)['\"]", all_dart_text))

all_keys = en_keys | fr_keys | ar_keys | used_keys

en_missing = all_keys - en_keys
fr_missing = all_keys - fr_keys
ar_missing = all_keys - ar_keys

print(f"Missing in EN: {en_missing}")
print(f"Missing in FR: {fr_missing}")
print(f"Missing in AR: {ar_missing}")
