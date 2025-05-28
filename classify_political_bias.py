
from fastapi import FastAPI, Request
from pydantic import BaseModel
from transformers import pipeline
import uvicorn

class BiasRequest(BaseModel):
    text: str

app = FastAPI()

classifier = pipeline("text-classification", model="bucketresearch/politicalBiasBERT")

@app.post("/classify")
async def classify(request: BiasRequest):
    result = classifier(request.text)
    return result[0]

if __name__ == "__main__":
    uvicorn.run("classify_political_bias:app", host="0.0.0.0", port=8000, reload=True)
