import os
import cv2
import numpy as np
from fastapi import FastAPI, Depends, HTTPException, status, File, UploadFile
from fastapi.security import APIKeyHeader
from pydantic import BaseModel

from insightface.app import FaceAnalysis

app = FastAPI(title="Face Feature Extraction API")

face_analyzer = FaceAnalysis(name='buffalo_l')
face_analyzer.prepare(ctx_id=-1, det_size=(640, 640))

API_KEY_NAME = "X-Internal-API-Key"
api_key_header = APIKeyHeader(name=API_KEY_NAME, auto_error=False)
SECRET_API_KEY = os.getenv("SECRET_API_KEY", "super-secret-internal-key")

def get_api_key(api_key_header: str = Depends(api_key_header)):
    if api_key_header == SECRET_API_KEY:
        return api_key_header
    raise HTTPException(
        status_code=status.HTTP_403_FORBIDDEN,
        detail="Could not validate credentials"
    )

class EmbeddingResponse(BaseModel):
    embedding: list[float]

@app.post("/api/v1/get-embedding", response_model=EmbeddingResponse)
async def extract_face_embedding(
    image: UploadFile = File(...),
    api_key: str = Depends(get_api_key)
):
    try:
        content = await image.read()
        
        np_img = np.frombuffer(content, np.uint8)
        img_bgr = cv2.imdecode(np_img, cv2.IMREAD_COLOR)
        
        if img_bgr is None:
            raise HTTPException(status_code=400, detail="Formato de imagen inválido o corrupto.")

        faces = face_analyzer.get(img_bgr)
        
        if len(faces) == 0:
            raise HTTPException(status_code=400, detail="No se detectó ningún rostro en la imagen.")
            
        face = faces[0]
        
        embedding = face.embedding.tolist()
        
        return {"embedding": embedding}
        
    except HTTPException as he:
        raise he
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
