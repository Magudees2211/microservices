FROM python:3.11

WORKDIR /app

COPY . .

RUN pip install fastapi uvicorn redis

EXPOSE 9000

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "9000"]