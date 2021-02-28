function fileValidation(file)
{
    if($(file).length)
    {
        if((file.type == "image/png") || (file.type == "image/jpg") || (file.type == "image/gif") || (file.type == "image/jpeg"))
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
    } else {
        return false;
    }
    
}