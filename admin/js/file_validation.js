function fileValidation(file)
{
    if((file.type == "image/png") || (file.type == "image/jpg") || (file.type == "image/gif"))
    {
        if(file.size <= 5000000)
        {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}