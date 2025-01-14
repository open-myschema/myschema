from fastapi import FastAPI
from pydantic import BaseModel

class Request(BaseModel):
    app: str
    module: str
    script: str
    args: str | None = None

app = FastAPI()

@app.post('/')
def respond(request: Request):
    return request
