import { initCollection, initMongoDB } from "../config/db.js";


const sendMessage = async (req, res) => {
    const { sender ,receiver, message } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        
        // Create a timestamp
        const timestamp = new Date(); // Current date and time
        
        // Insert the message along with the timestamp
        const result = await messageCollection.insertOne({ sender, receiver, message, timestamp });
        
        if (!result.acknowledged) {
            return res.status(400).json({ message: 'Error sending message' });
        }
        
        return res.status(200).json({ message: 'Message sent successfully' });
        
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: "Internal Server Error" });
    }
};

export const getMessage = async (req, res) => {
    const { sender, receiver } = req.body;
    console.log(req.body); // Logging request body for debugging purposes
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        
        // Fetch messages from MongoDB
        const messagesCursor = messageCollection.find({ sender, receiver });
        
        // Convert cursor to array
        const messages = await messagesCursor.toArray();

        // Map the messages to a simplified object without circular references
        const simplifiedMessages = messages.map(msg => ({
            sender: msg.sender,
            receiver: msg.receiver,
            message: msg.message,
            timestamp: msg.timestamp,
        }));

        // Send the response with simplified messages
        return res.status(200).json({ messages: simplifiedMessages });
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: "Internal Server Error", err: err.message });
    }
};



export default sendMessage;