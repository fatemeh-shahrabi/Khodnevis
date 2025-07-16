import sys
import whisper
import warnings

warnings.filterwarnings("ignore")

model = whisper.load_model("base")
audio_file = sys.argv[1]

result = model.transcribe(audio_file, language='fa')

sys.stdout.reconfigure(encoding='utf-8')

print(result["text"])

