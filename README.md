# OurPixels
## ITP 405 Final Project
- Name: Changyu Zhu
- Email: changyuz@usc.edu
Pixel art is widely used in games, especially for sprites and animations. However, beginners can hardly figure out the problem in their work and the way to improve it. It would be very helpful if professional pixel artists can give advice and help beginners create their pixel artworks. Also, it’s difficult for different people to collaborate on the same image and only work on the part they are best at. OurPixels is a website that allows users create pixel artworks and share them with others. It allows real-time collaboration and users can help others improve their artwork after finished.

## Audience
Everyone who is interested in pixel art, either fans or creators!
## Database
- Users: user information and credentials.
- ImageData: data of a specific artwork. With creator userId.
- Access: Specifies whether one user can view/edit a specific image. Includes one userId and one ImageId.

![Database Diagram](https://github.com/KCFindstr/ourpixels/database.png)

## Laravel
- GET routes for gallery, login, register, profile, and image page.
- POST routes for login, sign up, manage collaborator, create image, edit image metadata.
- All users inputs will be validated. Only authenticated users can create and edit pixel images. Users can authorize other users to edit image.
- User (hashed) credentials and images will be saved in the database.

## Node API
- GET endpoints to get the list of artworks and users / access information of a specific image or a specific user.
- POST endpoint to login and get temporary token / update image data.
- Real-time image data update and modification using web sockets.
