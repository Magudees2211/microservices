from fastapi import FastAPI
import redis

app = FastAPI()

r = redis.Redis(host="redis", port=6379)

@app.post("/track")
def track(code: str):

    r.incr(f"clicks:{code}")

    return {"status": "tracked"}