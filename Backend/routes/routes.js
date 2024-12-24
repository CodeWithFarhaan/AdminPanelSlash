import express from 'express';
import sendMessage, { getMessage } from '../controller/sendMsgContoller.js';
const router = express.Router();


router.post('/send-message', sendMessage);
router.post('/get-message', getMessage);


export default router