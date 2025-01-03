import express, { urlencoded, json } from "express";
import cors from "cors";
import { Server } from "socket.io";
import connectDB from "./config/db.js";
import dotenv from "dotenv";
import router from "./routes/routes.js";
import { Timestamp } from "mongodb";

const app = express();
dotenv.config();
app.use(cors());
app.use(urlencoded({ extended: true }));
app.use(json());
app.use('/api', router);

connectDB();
const server = app.listen(process.env.PORT, () => {
  console.log("server is running on port", process.env.PORT);
});


const io = new Server(server, {
  cors: {
    origin: process.env.CORS,
    credentials: true,
  },
});
io.on("connection", (socket) => {
  socket.on("join", function (data) {
    console.log(data);
    socket.join(data.data);
  });
  socket.on("send_message", async (data) => {
    const { sender, receiver, message } = data;
    socket.to(receiver).emit("receive_message",
      {
        sender,
        receiver,
        message,
        timestamp: new Date().toISOString(),
      }
    );
  });
});
