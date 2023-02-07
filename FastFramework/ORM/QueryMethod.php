<?php

namespace FastFramework\ORM;

enum QueryMethod
{
    case INSERT;
    case DELETE;
    case UPDATE;
    case SELECT;
    case CUSTOM;
}