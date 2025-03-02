import sys
import json
from transformers import pipeline

def classify_text(text):
    pipe = pipeline("text-classification", model="bucketresearch/politicalBiasBERT")
    result = pipe(text)
    return result[0]

if __name__ == "__main__":
    content = sys.argv[1]
    result = classify_text(content)
    print(json.dumps(result))
