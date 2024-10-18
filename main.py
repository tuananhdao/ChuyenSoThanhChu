import re

def main(my_number):
    my_number = re.sub(r"[^0-9]", "", my_number)
    vnd = ""
    place = ["", "", " Ngàn ", " Triệu ", " Tỷ ", " Ngàn Tỷ "]

    # Convert my_number to a string, trimming extra spaces
    my_number = my_number.strip()

    # Find decimal place
    decimal_place = my_number.find(".")

    # If we find a decimal place
    if decimal_place > 0:
        # Convert cents
        temp = my_number[decimal_place + 1:].ljust(2, '0')[:2]
        cents = convert_tens(temp)
        # Strip off cents from remainder to convert
        my_number = my_number[:decimal_place].strip()

    count = 1
    while my_number:
        # Convert last 3 digits of my_number to VND
        temp = convert_hundreds(my_number[-3:])
        if temp:
            vnd = temp + place[count] + vnd
        if len(my_number) > 3:
            # Remove last 3 converted digits from my_number
            my_number = my_number[:-3]
        else:
            my_number = ""
        count += 1

    # Clean up VND
    vnd = vnd.strip()
    if not vnd:
        vnd = "Không Ngàn"
    elif vnd == "Một":
        vnd = "Một Ngàn"
    else:
        vnd = vnd + " Đồng"

    return vnd


def convert_hundreds(my_number):
    result = ""

    # Exit if there's nothing to convert
    if int(my_number) == 0:
        return ""

    # Append leading zeros to number
    my_number = my_number.zfill(3)

    # Convert hundreds place digit
    if my_number[0] != "0":
        result = convert_digit(my_number[0]) + " Trăm "

    # Convert tens place digit
    if my_number[1] != "0":
        result += convert_tens(my_number[1:])
    else:
        # Convert ones place digit
        result += convert_digit(my_number[2])

    return result.strip()


def convert_tens(my_tens):
    result = ""

    # Is the value between 10 and 19?
    if int(my_tens[0]) == 1:
        result = {
            10: "Mười",
            11: "Mười Một",
            12: "Mười Hai",
            13: "Mười Ba",
            14: "Mười Bốn",
            15: "Mười Lăm",
            16: "Mười Sáu",
            17: "Mười Bảy",
            18: "Mười Tám",
            19: "Mười Chín"
        }.get(int(my_tens), "")
    else:
        # Otherwise, it's between 20 and 99
        tens_dict = {
            2: "Hai Mươi ",
            3: "Ba Mươi ",
            4: "Bốn Mươi ",
            5: "Năm Mươi ",
            6: "Sáu Mươi ",
            7: "Bảy Mươi ",
            8: "Tám Mươi ",
            9: "Chín Mươi "
        }
        result = tens_dict.get(int(my_tens[0]), "")
        # Convert ones place digit
        result += convert_digit(my_tens[1])

    return result


def convert_digit(my_digit):
    return {
        1: "Một",
        2: "Hai",
        3: "Ba",
        4: "Bốn",
        5: "Năm",
        6: "Sáu",
        7: "Bảy",
        8: "Tám",
        9: "Chín"
    }.get(int(my_digit), "")
