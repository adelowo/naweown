# Naweown

Some sample Laravel app built with TDD in mind......

## Overview

The app is going to be a sort of a listing app. Think of it as a listing app for some handmade crafts.

- [x] Users can login/register (password-less login).
- [x] Users can post items of stuff. 
- [x] Users can delete stuffs. See 2. 
- [x] Users can follow other users. 
- [x] Users have a profile page. 
- [x] Users can unfollow other users.
- [x] Users can delete their accounts.
- [x] Users can update their account details.
- [] Social login. This is to complement the magic link.
  - [] Facebook
  - [] Google
- [] API
  - [x] Users' profile can be fetched.
  - [x] User's items can be fetched.
  - [x] All items can ve fetched.
  - [x] All users can be fetched.

### Hey Yo, where is the (G)UI ? 
There currently isn't no GUI and ___i don't think there'd be one in the future___. I am just taking TDD for a test drive.

### If there is not a (G)UI, how do i know the app works ? 
Run `phpunit --testdox` then read everything in the `tests`.
